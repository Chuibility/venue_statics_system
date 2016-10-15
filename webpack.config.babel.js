import path from 'path';
import webpack from 'webpack';

function root(fn) {
  return path.resolve(__dirname, fn);
}

export default {
  context: root('.'),
  watchOptions: {
    aggregateTimeout: 1000
  },
  entry: {
    'gate': './entry.js'
  },
  resolve: {
    modules: [root('node_modules'), root('vj4/ui')],
    extensions: ['.js', '']
  },
  output: {
    path: __dirname,
    filename: 'bundle.js'
  },
  module: {
    loaders: [
      {
        test: /\.js$/,
        exclude: /node_modules\//,
        loader: 'babel',
        query: {
          ...require('./package.json').babelForProject
        }
      }
    ]
  }
};
