import keyboard
import win32gui
import time
from ctypes import *
from datetime import datetime
import threading
import os
import sys
from plyer import notification

def checkKeyboard(x):
    if x.event_type == 'down' and x.name == 'right ctrl':
      os.execv(sys.executable, [sys.executable] + sys.argv)
    else:
      print("Блокирование по нажатию клавишы", datetime.now())
      user32=windll.LoadLibrary('user32.dll')
      user32.LockWorkStation()

def stateKeyboard():
  time.sleep(1)
  keyboard.hook(checkKeyboard)
  keyboard.wait()

def checkMouse():
  while True:
    flags, hCursor, (x, y) = win32gui.GetCursorInfo()
    oldX = x
    oldY = y
    time.sleep(1)
    while True:
      flags, hCursor, (x, y) = win32gui.GetCursorInfo()
      if x != oldX and y != oldY:
        print("Блокирование по движению мышы", datetime.now())
        user32=windll.LoadLibrary('user32.dll')
        user32.LockWorkStation()


if __name__ == '__main__':
  keyboard.wait("ctrl+left")
  exec("screenLocker.py")  

  notification.notify(
    app_name='locker',
    title='Locker is Starting',
    message='Your PS is protected',
    ticker='Locker',
    app_icon='locker.ico',
    ) 

  threading.Thread(target = stateKeyboard).start()
  threading.Thread(target = checkMouse).start()
