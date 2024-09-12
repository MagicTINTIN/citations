#!/bin/bash
TOKEN=$( cat ../tk${PWD##*/} ) && export TOKEN && node index.js