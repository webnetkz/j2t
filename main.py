# -*- coding: utf-8 -*-

# coding:utf8


from selenium import webdriver

#options = webdriver.ChromeOptions() # Создание объекта настроек
#options.add_argument('headless') # Активация скрытого режима

EXE_PATH = r"C:\Users\User-B.I.G._03\Downloads\chromedriver_win32\driver.exe" # Путь до драйвера

driver = webdriver.Chrome(executable_path=EXE_PATH) #, chrome_options=options)

driver.get('https://web.whatsapp.com/')

searchInput = driver.find_element_by_css_selector('[title="Текстовое поле поиска"]')



