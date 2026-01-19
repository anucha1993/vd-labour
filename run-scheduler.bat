@echo off
cd /d D:\www\vd-labour
php artisan schedule:run >> D:\www\vd-labour\storage\logs\scheduler.log 2>&1