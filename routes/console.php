<?php

Schedule::command('queue:work --stop-when-empty')->everyMinute();

Schedule::command('project:replace-part-img-url')->when(fn() => false);

Schedule::command('project:update-beers-data')->hourly();

Schedule::command('project:export-db-table beers')->when(fn() => false);
