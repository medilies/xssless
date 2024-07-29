import { JSDOM } from 'jsdom';
import DOMPurify from 'dompurify';
import * as fs from 'fs';

if (process.argv.length < 3) {
    console.error(`Usage: ${process.argv[0]} ${process.argv[1]} [html_file_path]`);
    process.exit(1);
}

const htmlFile = process.argv[2];

const html = fs.readFileSync(htmlFile);

const window = new JSDOM('').window;
const purify = DOMPurify(window);

const clean = purify.sanitize(html);

fs.writeFileSync(htmlFile + ".clean", clean)

console.log(htmlFile + ".clean");
process.exit(0);
