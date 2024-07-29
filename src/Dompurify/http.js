import { JSDOM } from 'jsdom';
import DOMPurify from 'dompurify';
import http from 'http';

const window = new JSDOM('').window;
const purify = DOMPurify(window);

const host = 'localhost';
const port = 8000;

const requestListener = function (req, res) {
    if (req.method === 'POST') {
        let body = '';

        req.on('data', chunk => {
            body += chunk.toString();
        });

        req.on('end', () => {
            const clean = purify.sanitize(body);
            res.writeHead(200, { 'Content-Type': 'text/plain' });
            res.end(clean);
        });
    } else {
        res.writeHead(405, { 'Content-Type': 'text/plain' });
        res.end('Method Not Allowed');
    }
};

const server = http.createServer(requestListener);

server.listen(port, host, () => {
    console.log(`Server is running on http://${host}:${port}`);
});
