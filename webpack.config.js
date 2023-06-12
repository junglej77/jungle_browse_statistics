const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const glob = require('glob');

// JavaScript 入口配置
let jsEntries = {
    'public/js/jungle_browse_statistics-public': './src/public/js/jungle_browse_statistics-public.js',
    'admin/js/jungle_browse_statistics-admin': './src/admin/js/jungle_browse_statistics-admin.js',
    'admin/js/seogtp_statistics_overview': './src/admin/js/seogtp_statistics_overview.js',
};

// CSS 入口配置
let cssEntries = {
    'public/css/jungle_browse_statistics-public': './src/public/less/jungle_browse_statistics-public.less',
    'admin/css/jungle_browse_statistics-admin': './src/admin/less/jungle_browse_statistics-admin.less',
};

// JavaScript 配置
const jsConfig = {
    mode: 'development',
    entry: jsEntries,
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname),
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            [
                                '@babel/preset-env',
                                {
                                    targets: '> 0.25%, not dead',
                                    useBuiltIns: 'usage',
                                    corejs: 3,
                                },
                            ],
                        ],
                    }
                }
            },
        ],
    },
    optimization: {
        minimize: true,
        minimizer: [
            new TerserPlugin({
                extractComments: false,
            }),
        ],
    },
};

// CSS 配置
const cssConfig = {
    mode: 'development',
    entry: cssEntries,
    output: {
        filename: '[name].js',  // 这个输出的 .js 文件会是空的，你可以选择忽略它
        path: path.resolve(__dirname),
    },
    module: {
        rules: [
            {
                test: /\.less$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'less-loader',
                ],
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: ({ chunk }) => {
                return `${chunk.name}.css`;
            },
        }),
    ],
    optimization: {
        minimize: true,
        minimizer: [
            new CssMinimizerPlugin(),
        ],
    },
};

// 使用多配置模式
module.exports = [jsConfig, cssConfig];

