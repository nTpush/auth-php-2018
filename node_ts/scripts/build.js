"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var webpack = require("webpack");
var Webpack_config_1 = require("../config/Webpack.config");
var buildConfig = new Webpack_config_1.default('production');
webpack(buildConfig).run(function (err) {
    console.log(err);
});
//# sourceMappingURL=build.js.map