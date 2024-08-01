import { JSDOM } from 'jsdom';
import DOMPurify from 'dompurify';
import http from 'http';

if (process.argv.length < 4) {
    console.error(`Usage: ${process.argv[0]} ${process.argv[1]} <host> <port>`);
    process.exit(1);
}

const HOST = process.argv[2];
const PORT = process.argv[3];

const window = new JSDOM('').window;
const purify = DOMPurify(window);

/**
 * @param {http.IncomingMessage} req
 * @param {http.ServerResponse} res
 */
const requestListener = function (req, res) {
    if (req.method !== 'POST') {
        res.writeHead(405, { 'Content-Type': 'text/plain' });
        res.end('Method Not Allowed');
        return;
    }

    let body = '';

    req.on('data', chunk => {
        body += chunk.toString();
    });

    req.on('end', () => {
        try {
            const parsedBody = JSON.parse(body);

            if (!parsedBody.html || typeof parsedBody.html !== 'string') {
                res.writeHead(400, { 'Content-Type': 'text/plain' });
                res.end('Invalid HTML entry');
                return;
            }

            const clean = purify.sanitize(parsedBody.html);

            res.writeHead(200, { 'Content-Type': 'text/plain' });
            res.end(clean);
        } catch (e) {
            res.writeHead(400, { 'Content-Type': 'text/plain' });
            res.end('Invalid JSON');
            return;
        }
    });
};

const server = http.createServer(requestListener);

server.listen(PORT, HOST, () => {
    console.log(`Server is running on http://${HOST}:${PORT}`);
});
