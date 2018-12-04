"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var http = require("http");
var httpServer = http.createServer(function (req, res) {
    res.writeHead(200);
    res.end('Hello Typescript!');
});
httpServer.listen(9999, function () { return console.log('listening'); });
//# sourceMappingURL=main.js.map