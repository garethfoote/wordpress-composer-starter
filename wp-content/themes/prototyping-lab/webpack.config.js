var webpack = require('webpack');
var ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = {
  entry: "./src/app.js",
  output: {
    path: __dirname,
    filename: "./dist/js/bundle.js"
  },
  devtool: "source-map",
  module: {
    rules: [
      {
        test: /\.css$|\.styl$/,
        use: ExtractTextPlugin.extract({
          fallback: "style-loader",
          // use: "css-loader?sourceMap!postcss-loader?sourceMap!stylus-loader?sourceMap"
          use: [
            {
              loader: 'css-loader',
              options: { importLoaders: 1 },
            },
            {
              loader: 'postcss-loader',
            }
          ],
        })
      },
      {
        test: /\.woff$|.eot$|.svg$|.ttf$|.png$|.gif$|.jpg$|.jpeg$/,
        use: [{
          loader: "url-loader",
          options: {
            limit: 10000
          }
        }]
      }
    ]
  },
  plugins: [
    new ExtractTextPlugin("dist/css/bundle.css")
  ]
};
