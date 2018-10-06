from bs4 import BeautifulSoup
import requests

url = 'https://www.yandex.ru'

def url_get(url):
    return requests.get(url).text

html = url_get(url)

def pars_ya(html):
    soup = BeautifulSoup(html, 'lxml')
    #pars_weather = soup.find('div', class_='weather')
    #weather = soup.find('a', class_='link link_black_yes').get_text()
    weather = soup.find('a', class_='home-link home-link_black_yes')
    weather_a = weather['aria-label']

    if True == True:
        file = open('/Users/igor/Documents/test_html/parser.js', 'w')
        scr = """var q = document.getElementsByClassName('pogoda');
q[0].innerHTML = '""" + weather_a + """';
"""
        file.write(scr)
        file.close()
        print('Запись произведена успешно')

    return weather_a

#soup = BeautifulSoup(url, 'lxml')
print(pars_ya(html))


url_curs = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=&d=0&VAL_NM_RQ=R01235'

def url_curs_get(url_curs):
    return requests.get(url_curs).text

pars_curs = url_curs_get(url_curs)

def pars_usd(pars_curs):
    soup = BeautifulSoup(pars_curs, 'lxml')
    USD = soup.find(id='R01235')
    USD_pars = USD.value.text[0:5]

    if True == True:
        file = open('/Users/igor/Documents/test_html/parser.js','a')
        src = """var q = document.getElementsByClassName('USD');
q[0].innerHTML = '""" + USD_pars + """';
"""
        file.write(src)
        file.close()
        print('Запись значения USD закончена успешно')

    return USD_pars

def pars_eur(pars_curs):
    soup = BeautifulSoup(pars_curs, 'lxml')
    EUR = soup.find(id='R01239')
    EUR_pars = EUR.value.text[0:5]

    if True == True:
        file = open('/Users/igor/Documents/test_html/parser.js', 'a')
        src = """var q = document.getElementsByClassName('EUR');
q[0].innerHTML = '""" + EUR_pars + """';
"""
        file.write(src)
        file.close()
        print('Запись значения EUR закончена успешно')

    return EUR_pars

print('USD: ' + pars_usd(pars_curs))
print('EUR: ' + pars_eur(pars_curs))
