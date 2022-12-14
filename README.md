## Vocation 
Praction with using redis in queue handling.

## Description
Handling the queue in redis enablade due to laravel/horizon package (Horizon). Horizone includes the supervisor
to handle queue in redis.
Project as default guess a number, generates randomly number and equals both. If they equal job will be complited.
If they are not equal job will be failed. User can input his number and another params if he wish.
Project stores logs in redis`s list, writes to tail.
Project starts through GET request to API. 
Project can starts with default settings with request:
```angular2html
GET http://localhost:80/api/start
Accept: application/json

###
```
Or with params (which may be different):
```angular2html
GET http://localhost:80/api/start?tries=100&backoff=0&guess_number=32&range[start]=0&range[end]=200
Accept: application/json

###
```
Logs are available in redis server. To watch them you will need to execute in container:
```angular2html
redis-cli
```
```angular2html
keys *
```
```
lrange key 0 -1
```
Horizon provides UI, wich accessible at http://localhost/horizon

## How to run
* clone the repository
* ```docker-compose up -d```
* ```docker exec -it queueinredis_app_1 bash```
* ```service redis-server restart```
* ```php artisan horizon```
* Send GET request to http://localhost/api/start (You may use the browser`s address string)

### Some output example: 
```angular2html
127.0.0.1:6379> keys *
 1) "laravel_horizon:job:App\\Jobs\\Job"
 2) "laravel_horizon:24477a9f-0cc1-492b-ab7f-fdb63586a922"
 3) "laravel_database_laravel_cache_:U9U5iKASDrprvBVscP6VgodJjztwJnHzOT2zOy8k"
 4) "laravel_horizon:recent_jobs"
 5) "laravel_horizon:last_snapshot_at"
 6) "laravel_horizon:masters"
 7) "laravel_horizon:supervisors"
 8) "laravel_database_1662041940"
 9) "laravel_horizon:measured_queues"
10) "laravel_horizon:queue:default"
11) "laravel_database_1662041940_params"
12) "laravel_horizon:supervisor:cfed333ac8e1-q8Iv:supervisor-1"
13) "laravel_horizon:measured_jobs"
14) "laravel_horizon:master:cfed333ac8e1-q8Iv"
15) "laravel_horizon:completed_jobs"
16) "laravel_horizon:monitor:time-to-clear"
127.0.0.1:6379> lrange laravel_database_1662041940 0 -1
 1) "Params: backoff = 0 tries = 100 guessNumber = 50 start = 0 end = 100 "
 2) "Start date: 2022-09-01 14:19:00"
 3) "guessNumber = 50  randNumber = 33  status = Failed"
 4) "guessNumber = 50  randNumber = 95  status = Failed"
 5) "guessNumber = 50  randNumber = 84  status = Failed"
 6) "guessNumber = 50  randNumber = 48  status = Failed"
 7) "guessNumber = 50  randNumber = 70  status = Failed"
 8) "guessNumber = 50  randNumber = 79  status = Failed"
 9) "guessNumber = 50  randNumber = 90  status = Failed"
10) "guessNumber = 50  randNumber = 24  status = Failed"
11) "guessNumber = 50  randNumber = 17  status = Failed"
12) "guessNumber = 50  randNumber = 73  status = Failed"
13) "guessNumber = 50  randNumber = 7  status = Failed"
14) "guessNumber = 50  randNumber = 13  status = Failed"
15) "guessNumber = 50  randNumber = 34  status = Failed"
16) "guessNumber = 50  randNumber = 16  status = Failed"
17) "guessNumber = 50  randNumber = 44  status = Failed"
18) "guessNumber = 50  randNumber = 39  status = Failed"
19) "guessNumber = 50  randNumber = 70  status = Failed"
20) "guessNumber = 50  randNumber = 27  status = Failed"
21) "guessNumber = 50  randNumber = 47  status = Failed"
22) "guessNumber = 50  randNumber = 74  status = Failed"
23) "guessNumber = 50  randNumber = 44  status = Failed"
24) "guessNumber = 50  randNumber = 88  status = Failed"
25) "guessNumber = 50  randNumber = 100  status = Failed"
26) "guessNumber = 50  randNumber = 79  status = Failed"
27) "guessNumber = 50  randNumber = 5  status = Failed"
28) "guessNumber = 50  randNumber = 67  status = Failed"
29) "guessNumber = 50  randNumber = 67  status = Failed"
30) "guessNumber = 50  randNumber = 68  status = Failed"
31) "guessNumber = 50  randNumber = 32  status = Failed"
32) "guessNumber = 50  randNumber = 20  status = Failed"
33) "guessNumber = 50  randNumber = 36  status = Failed"
34) "guessNumber = 50  randNumber = 96  status = Failed"
35) "guessNumber = 50  randNumber = 30  status = Failed"
36) "guessNumber = 50  randNumber = 15  status = Failed"
37) "guessNumber = 50  randNumber = 8  status = Failed"
38) "guessNumber = 50  randNumber = 22  status = Failed"
39) "guessNumber = 50  randNumber = 23  status = Failed"
40) "guessNumber = 50  randNumber = 40  status = Failed"
41) "guessNumber = 50  randNumber = 42  status = Failed"
42) "guessNumber = 50  randNumber = 24  status = Failed"
43) "guessNumber = 50  randNumber = 72  status = Failed"
44) "guessNumber = 50  randNumber = 65  status = Failed"
45) "guessNumber = 50  randNumber = 94  status = Failed"
46) "guessNumber = 50  randNumber = 58  status = Failed"
47) "guessNumber = 50  randNumber = 58  status = Failed"
48) "guessNumber = 50  randNumber = 90  status = Failed"
49) "guessNumber = 50  randNumber = 11  status = Failed"
50) "guessNumber = 50  randNumber = 35  status = Failed"
51) "guessNumber = 50  randNumber = 12  status = Failed"
52) "guessNumber = 50  randNumber = 10  status = Failed"
53) "guessNumber = 50  randNumber = 10  status = Failed"
54) "guessNumber = 50  randNumber = 8  status = Failed"
55) "guessNumber = 50  randNumber = 83  status = Failed"
56) "guessNumber = 50  randNumber = 90  status = Failed"
57) "guessNumber = 50  randNumber = 74  status = Failed"
58) "guessNumber = 50  randNumber = 76  status = Failed"
59) "guessNumber = 50  randNumber = 83  status = Failed"
60) "guessNumber = 50  randNumber = 37  status = Failed"
61) "guessNumber = 50  randNumber = 54  status = Failed"
62) "guessNumber = 50  randNumber = 70  status = Failed"
63) "guessNumber = 50  randNumber = 75  status = Failed"
64) "guessNumber = 50  randNumber = 24  status = Failed"
65) "guessNumber = 50  randNumber = 24  status = Failed"
66) "guessNumber = 50  randNumber = 3  status = Failed"
67) "guessNumber = 50  randNumber = 96  status = Failed"
68) "guessNumber = 50  randNumber = 86  status = Failed"
69) "guessNumber = 50  randNumber = 36  status = Failed"
70) "guessNumber = 50  randNumber = 61  status = Failed"
71) "guessNumber = 50  randNumber = 40  status = Failed"
72) "guessNumber = 50  randNumber = 69  status = Failed"
73) "guessNumber = 50  randNumber = 42  status = Failed"
74) "guessNumber = 50  randNumber = 25  status = Failed"
75) "guessNumber = 50  randNumber = 19  status = Failed"
76) "guessNumber = 50  randNumber = 69  status = Failed"
77) "guessNumber = 50  randNumber = 36  status = Failed"
78) "guessNumber = 50  randNumber = 55  status = Failed"
79) "guessNumber = 50  randNumber = 25  status = Failed"
80) "guessNumber = 50  randNumber = 63  status = Failed"
81) "guessNumber = 50  randNumber = 32  status = Failed"
82) "guessNumber = 50  randNumber = 77  status = Failed"
83) "guessNumber = 50  randNumber = 97  status = Failed"
84) "guessNumber = 50  randNumber = 22  status = Failed"
85) "guessNumber = 50  randNumber = 87  status = Failed"
86) "guessNumber = 50  randNumber = 16  status = Failed"
87) "guessNumber = 50  randNumber = 58  status = Failed"
88) "guessNumber = 50  randNumber = 50  status = OK"
127.0.0.1:6379> c

```
