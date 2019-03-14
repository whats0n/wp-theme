const Browser = require('browser-sync')
const webpack = require('webpack')
const webpackDevMiddleware = require('webpack-dev-middleware')
const webpackConfig = require('./webpack').config

const browser = Browser.create()
const bundler = webpack(webpackConfig)

function server() {

  let config = {
    mode: 'development',
    server: 'assets',
    open: false,
    middleware: [
      webpackDevMiddleware(bundler, { /* options */ })
    ],
  }

  browser.init(config)

  return browser
}

module.exports = server