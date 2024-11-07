module.exports = {
  publicPath: "./",
  parallel: false,
  devServer: {
    https: false,
    proxy: {
      "/api": {
        target: "http://127.0.0.1:8081/",
        ws: true,
        changeOrigin: true,
        logLevel: "debug",
      },
    },
  },
  css: {
    loaderOptions: {
      sass: {
        prependData: `@import "src/assets/scss/main.scss";`,
      },
    },
  },
};
