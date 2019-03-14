const path    = require('path')
const webpack = require('webpack')
const process = require('process')
const config  = require('./config')

const isProduction = (process.env.NODE_ENV === 'production')

const webpachConfig = {

  mode: isProduction ? 'production' : 'development',
  entry: `./${config.src.root + config.src.scripts}main.js`,

  output: {
    filename: `bundle.js`,
    path: path.resolve(__dirname, `../${config.build.scripts}`)
  },

  plugins: isProduction ? [ new webpack.optimize.UglifyJsPlugin() ] : []
}

module.exports = {
  config: webpachConfig,
  scripts: () => new Promise(resolve => webpack(webpachConfig, (err, stats) => {

    if(err) console.log('Webpack', err)

    console.log(stats.toString({ /* stats options */ }))

    resolve()
  }))
}