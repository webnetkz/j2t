import sys
from PyQt5 import QtCore, QtGui, QtWidgets
from PyQt5.QtWidgets import QApplication, QFrame
from PyQt5.QtCore    import QTimer, Qt


def showLocker():
  app = QApplication(sys.argv)
  frame = QFrame()
  frame.setStyleSheet("""
        QFrame {
            background-color: green;
        }
        """)
  frame.resize(5000, 2)
  frame.setWindowOpacity(1)
  frame.setWindowFlags(Qt.SplashScreen | Qt.FramelessWindowHint)
  frame.show()
  sys.exit(app.exec_())