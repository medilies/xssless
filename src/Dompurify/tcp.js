import { JSDOM } from 'jsdom';
import DOMPurify from 'dompurify';
import net from 'net';

const window = new JSDOM('').window;
const purify = DOMPurify(window);

const server = net.createServer((socket) => {
    console.log('Client connected');

    let buffer = Buffer.alloc(0);
    let expectedLength = null;

    socket.on('data', (chunk) => {
        buffer = Buffer.concat([buffer, chunk]);

        // Check if we have received the length prefix
        if (expectedLength === null && buffer.length >= 4) {
            expectedLength = buffer.readUInt32BE(0);
            buffer = buffer.subarray(4); // Remove the length prefix from the buffer
        }

        // Check if we have received the complete message
        if (expectedLength !== null && buffer.length >= expectedLength) {
            const data = buffer.subarray(0, expectedLength).toString();
            console.log('Received data: ' + `${data.length} bytes`);

            // Process the data and send a response
            const response = purify.sanitize(data);
            socket.write(response, () => {
                console.log('Response sent: ' + `${response.length} bytes`);
                // Close the connection after sending the response
                socket.end();
            });

            // Clear the buffer and reset expectedLength
            buffer = buffer.subarray(expectedLength);
            expectedLength = null;
        }
    });

    socket.on('end', () => {
        console.log('Client disconnected');
    });

    socket.on('error', (err) => {
        console.error('Socket error:', err);
    });
});

const PORT = 63000;
server.listen(PORT, () => {
    console.log(`Server listening on port ${PORT}`);
});

server.on('error', (err) => {
    console.error('Server error:', err);
});
