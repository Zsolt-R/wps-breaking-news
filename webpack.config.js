// https://carrieforde.com/webpack-wordpress/
const path = require("path"),
    webpack = require("webpack"),
    MiniCssExtractPlugin = require("mini-css-extract-plugin"),
    BrowserSyncPlugin = require('browser-sync-webpack-plugin');
module.exports = [{
    name: 'plugin-public',
    entry: [
        "./public/assets/src/js/wps-breaking-news-plugin.js",
        "./public/assets/src/scss/wps-breaking-news-plugin.scss"
    ],
    output: {
        path: path.resolve(__dirname, "public/assets/dist"),
        filename: "js/wps-breaking-news-plugin.min.js",
        publicPath: "../"
    },
    module:{
        rules:[{
            test: /\.js$/,
            exclude: /(node_modules)/,
            use: {
                loader: "babel-loader",
                options: {
                    presets: ["@babel/preset-env"]
                }
            }
        },{
            test: /\.(sa|sc|c)ss$/,
            use: [
                { loader: MiniCssExtractPlugin.loader},
                {loader: "css-loader"},
                {loader: "postcss-loader"},
                {
                    loader: "sass-loader",
                    options: {
                        implementation: require("sass")
                    }
                }
                ]

        },{
            test: /\.(png|jpe?g|gif|svg)$/i,
            use: [
                {
                    loader: "file-loader",
                    options: {
                        name: "[name].[ext]",
                        outputPath: "images"
                    }
                }
            ]
        }]
    },
    plugins: [
        new webpack.DefinePlugin({
            ENV: JSON.stringify("plugin-public")
        }),
        new MiniCssExtractPlugin({
            filename: "css/wps-breaking-news-plugin.min.css"
        }),
        new BrowserSyncPlugin({
            files: '**/*.php',
            proxy: 'https://toptal.local/'
        })
    ]
},{
    name: 'plugin-admin',
    entry: [
        "./admin/assets/src/js/wps-breaking-news-plugin-admin.js",
        "./admin/assets/src/scss/wps-breaking-news-plugin-admin.scss"
    ],
    output: {
        path: path.resolve(__dirname, "admin/assets/dist"),
        filename: "js/wps-breaking-news-plugin-admin.min.js",
        publicPath: "../"
    },
    module:{
        rules:[ {
            test: /\.js$/,
            exclude: /(node_modules)/,
            use: {
                loader: "babel-loader",
                options: {
                    presets: ["@babel/preset-env"]
                }
            }
        },{
            test: /\.(sa|sc|c)ss$/,
            use: [
                { loader: MiniCssExtractPlugin.loader},
                {loader: "css-loader"},
                {loader: "postcss-loader"},
                {
                    loader: "sass-loader",
                    options: {
                        implementation: require("sass")
                    }
                }
            ]

        },{
            test: /\.(png|jpe?g|gif|svg)$/i,
            use: [
                {
                    loader: "file-loader",
                    options: {
                        name: "[name].[ext]",
                        outputPath: "images"
                    }
                }
            ]
        }]
    },
    plugins: [
        new webpack.DefinePlugin({
            ENV: JSON.stringify("plugin-public")
        }),
        new MiniCssExtractPlugin({
            filename: "css/wps-breaking-news-plugin-admin.min.css"
        })
    ]
}, {
    name: "customizer",
    entry: ["./admin/assets/src/js/wps-breaking-news-customizer.js"],
    output: {
        path: path.resolve(__dirname, "admin/assets/dist"),
        filename: "js/wps-breaking-news-customizer.min.js",
        publicPath: "../"
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules)/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: ["@babel/preset-env"]
                    }
                }
            }
        ]
    },
    plugins: [
        new webpack.DefinePlugin({
            ENV: JSON.stringify("customizer")
        })
    ]
}]