#!/bin/bash
# npm startのエラーログを取得するスクリプト
echo "Running npm start and capturing errors..."
npm start 2> webpack-error.log
echo "Error log saved to webpack-error.log"