#!/bin/bash

mkdir -p $(pwd)/storage/app/public/images
ln -s $(pwd)/resources/images/banks $(pwd)/storage/app/public/images
ln -s $(pwd)/resources/images/brands-svg $(pwd)/storage/app/public/images