#!/bin/bash
eval $@ | sed -r "s/\x1B\[([0-9]{1,2})?\)?[mGKCA]//g"
