import http from 'http';

if (process.argv.length < 4) {
    console.error(`Usage: ${process.argv[0]} ${process.argv[1]} <host> <port>`);
    process.exit(1);
}

const HOST = process.argv[2];
const PORT = process.argv[3];

/**
 * @param {http.IncomingMessage} req
 * @param {http.ServerResponse} res
 */
const requestListener = function (req, res) {
    res.writeHead(422);
    res.end("Meh");
};

const server = http.createServer(requestListener);

server.listen(PORT, HOST, () => {
    console.log(`Server is running on http://${HOST}:${PORT}`);
});
