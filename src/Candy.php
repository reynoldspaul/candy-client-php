<?php

namespace GetCandy\Client;

use Closure;

class Candy
{
    protected $callChain = [];
    protected $params;
    protected $returnJob = false;
    protected $job;

    public function __call($name, $arguments)
    {

        $this->callChain[] = $name;

        if (count($arguments) == 1) {
            $this->params = $arguments[0];
        } elseif (count($arguments)) {
            $this->params = $arguments;
        }

        if ($this->canRun()) {
            if ($this->returnJob) {
                return $this->getJob();
            } else {
                return $this->execute()->getResponse();
            }
        }

        return $this;
    }

    public function job()
    {
        $this->returnJob = true;

        return $this;
    }

    public function canRun()
    {
        // Do we have a job for this call chain?
        return class_exists($this->getClassFromChain());
    }

    public function getJob()
    {
        $jobClass = $this->getClassFromChain();

        $this->job = new $jobClass($this->params);

        $this->reset();

        return $this->job;
    }

    public function execute()
    {
        $job = $this->getJob();

        $batch = new BatchRequestService();

        $batch->add($job);
        $batch->execute();

        $this->reset();

        return $job->getRequest();
    }

    public function batch(Closure $addJobs)
    {
        $batch = new \GetCandy\LaravelClient\BatchRequestService();

        $addJobs($batch);

        $batch->execute();

        $responses = [];

        foreach ($batch->getJobs() as $key => $job) {
            $responses[$key] = $job->getRequest()->getResponse();
        }

        return $responses;
    }

    public function reset()
    {
        $this->callChain = [];
        $this->params = [];
        $this->returnJob = false;
    }

    private function getClassFromChain()
    {
        return "\\GetCandy\\LaravelClient\\Jobs\\" . implode("\\", array_map("ucfirst", $this->callChain));
    }
}
