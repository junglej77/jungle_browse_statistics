const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const glob = require('glob');

let entries = {
    'public/js/jungle_browse_statistics-public': './src/public/js/jungle_browse_statistics-public.js',
    'admin/js/jungle_browse_statistics-admin': './src/admin/js/jungle_browse_statistics-admin.js',
};
glob.sync('./src/*/*.less').forEach(filepath => {
    let name = filepath.replace('.less', '');
    entries[name] = filepath;
});
module.exports = {
    mode: 'development',
    entry: entries,
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname),
        publicPath: '/',
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.less$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            publicPath: '../',  // 这里可以根据实际情况进行调整
                        },
                    },
                    'css-loader',
                    'less-loader',
                ],
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: ({ chunk }) => {
                return chunk.name.replace('src', '').replace('.js', '.css');
            }
        }),
    ],
    optimization: {
        minimize: true,
        minimizer: [
            new CssMinimizerPlugin(),
            new TerserPlugin({
                extractComments: false,
            }),
        ],
    },
};
