"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var path = require("path");
var StartServerPlugin = require("start-server-webpack-plugin");
var webpack = require("webpack");
var nodeExternals = require("webpack-node-externals");
var WebpackConfig = /** @class */ (function () {
    function WebpackConfig(mode) {
        var _a;
        // node环境
        this.target = "node";
        // 默认为发布环境
        this.mode = 'production';
        // 入口文件
        this.entry = [path.resolve(__dirname, '../server/server.ts')];
        this.output = {
            path: path.resolve(__dirname, '../dist'),
            filename: "server.js"
        };
        // 这里为开发环境留空
        this.externals = [];
        // loader们
        this.module = {
            rules: [
                {
                    test: /\.tsx?$/,
                    use: [
                        // tsc编译后，再用babel处理
                        { loader: 'babel-loader', },
                        {
                            loader: 'ts-loader',
                            options: {
                                // 加快编译速度
                                transpileOnly: true,
                                // 指定特定的ts编译配置，为了区分脚本的ts配置
                                configFile: path.resolve(__dirname, './tsconfig.json')
                            }
                        }
                    ],
                    exclude: /node_modules/
                },
                {
                    test: /\.jsx?$/,
                    use: 'babel-loader',
                    exclude: /node_modules/
                }
            ]
        };
        this.resolve = {
            extensions: [".ts", ".js", ".json"],
        };
        // 开发环境也使用NoEmitOnErrorsPlugin
        this.plugins = [new webpack.NoEmitOnErrorsPlugin()];
        // 配置mode，production情况下用上边的默认配置就ok了。
        this.mode = mode;
        if (mode === 'development') {
            // 添加webpack/hot/signal,用来热更新
            this.entry.push('webpack/hot/signal');
            this.externals.push(
            // 添加webpack/hot/signal,用来热更新
            nodeExternals({
                whitelist: ['webpack/hot/signal']
            }));
            var devPlugins = [
                // 用来热更新
                new webpack.HotModuleReplacementPlugin(),
                // 启动服务
                new StartServerPlugin({
                    // 启动的文件
                    name: 'server.js',
                    // 开启signal模式的热加载
                    signal: true,
                    // 为调试留接口
                    nodeArgs: ['--inspect']
                }),
            ];
            (_a = this.plugins).push.apply(_a, devPlugins);
        }
    }
    return WebpackConfig;
}());
exports.default = WebpackConfig;
//# sourceMappingURL=Webpack.config.js.map