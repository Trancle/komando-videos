<?php



header("Content-disposition: attachment; filename=sm_subcribers-list.csv");
header("Content-type: application/csv");
readfile("data/sm_subcribers-list.csv");





?>