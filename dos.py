#-- coding: utf8 --
#!/usr/bin/env python3

import time, requests

r = requests.get('https://api.github.com/users/IDerevyansky')


if r.status_code == 200 :
	print('request take')




# print(str(x)+' work');
# print(time.process_time())
	
