"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var http = require("http");
var app_1 = require("./app");
var currentApp = app_1.default.callback();
var server = http.createServer(currentApp);
server.listen(7777);
if (module.hot) {
    module.hot.accept('./app.ts', function () {
        server.removeListener('request', currentApp);
        currentApp = app_1.default.callback();
        server.on('request', currentApp);
    });
}
//# sourceMappingURL=server.js.map