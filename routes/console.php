<?php

Schedule::command('queue:work --stop-when-empty')->everyMinute();

Schedule::command('project:replace-part-img-url')->when(fn() => false);

Schedule::command('project:update-ubeers-data')->hourly();

Schedule::command('project:export-db-table ubeers')->when(fn() => false);
