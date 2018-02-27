<?php

namespace GetCandy\Client;

interface JobInterface
{
    public function getRequests();
    public function run();
}
