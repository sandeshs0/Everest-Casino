from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from multiprocessing import Process
from time import sleep
import os, json, random
from fake_useragent import UserAgent

with open('Config.json', 'r') as openfile:
    # Reading from json file
    json_object = json.load(openfile)


link = json_object['url']
py = json_object['proxy']
thread = int(json_object['thread'])


prox = open(py,'r')
pro = prox.readlines()

proxy_list = []
for _ in pro:
    proxy_list.append(_.strip())


try:
    ua = UserAgent()
    userAgent = ua.random 
except:
    ua = UserAgent()
    userAgent = ua.random 



def drive():
    s = Service(executable_path='chromedriver.exe')
    options = webdriver.ChromeOptions()
    options.add_argument("--ignore-certificate-errors")
    options.add_argument("--incognito")
    options.add_argument("--log-level=3")
    options.add_argument('--proxy-server=%s' % py)
    driver = webdriver.Chrome(options=options)
    return driver


def main():
    
    while True:
        li_element = []
        try:
            driver = drive()
            driver.maximize_window()
            driver.get(link)
            driver.implicitly_wait(10)
            
            li_element = driver.find_element(By.CLASS_NAME,"social").find_element(By.CLASS_NAME,'social-icons').find_elements(By.TAG_NAME,"a")
            social_share = driver.find_element(By.CLASS_NAME,"social").find_element(By.TAG_NAME,"a")
            sleep(1)
            

        except Exception as e:
            pass
        main_window = driver.current_window_handle

        # click on favorite button 
        try:
            driver.execute_script("document.querySelector('.token-name').querySelector('a').click();")
        except:
            pass

        #___________________________________________ Click on  website
        for url in li_element:
            try:
                driver.execute_script("arguments[0].click();", url)
                sleep(10)
                new_window = driver.window_handles[1]
                driver.switch_to.window(new_window)
                sleep(10)
                driver.close()
                driver.switch_to.window(main_window)
            except Exception as e:
                pass
        
        
        # _________________________________________share button click and open model window
        try:
            driver.execute_script("arguments[0].click();", social_share)
            sleep(2)
            model_content = driver.find_element(By.CLASS_NAME,"modal-content")


            # ______________________________________ looping over each social site
            try:
                social_link = model_content.find_elements(By.TAG_NAME,"a")
                # for _ in random.randint(0,2):
                driver.execute_script("arguments[0].click();", social_link[random.randint(0,2)])
                sleep(10)
                new_window = driver.window_handles[1]
                driver.switch_to.window(new_window)
                driver.close()
                driver.switch_to.window(main_window)
            except Exception as e:
                pass
        except Exception as e:
            pass

        # _______________________________________________________ Close model window
        try:
            driver.execute_script("document.querySelector('.modal-content').querySelector('button').click();")
        except Exception as e:
            pass

        driver.delete_all_cookies()
        driver.quit()



if __name__=='__main__':
    for _ in range(thread):
        process_obj = Process(target=main)
        process_obj.start()

    for __ in range(thread):
        process_obj.join()
