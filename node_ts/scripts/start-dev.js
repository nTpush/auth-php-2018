"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var webpack = require("webpack");
var Webpack_config_1 = require("../config/Webpack.config");
// 创建编译时配置
var devConfig = new Webpack_config_1.default('development');
// 通过watch来实时编译
webpack(devConfig).watch({
    aggregateTimeout: 300
}, function (err) {
    console.log(err);
});
//# sourceMappingURL=start-dev.js.map