import socks
import socket

def test_socks5(host, port, username, password):
    socks.set_default_proxy(socks.SOCKS5, host, port, username=username, password=password)
    socket.setdefaulttimeout(10)  # 設置超時時間

    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        s.connect(('www.baidu.com', 80))  # 嘗試連接 Google
        print("連接成功！SOCKS5 代理可用")
        return True
    except Exception as e:
        print(f"連接失敗：{e}")
        return False
    finally:
        s.close()

# 替換為您的 SOCKS5 代理伺服器資訊
socks5_host = '203.6.230.122'
socks5_port = 20401
socks5_username = 'user1'
socks5_password = '078Su4'

if test_socks5(socks5_host, socks5_port, socks5_username, socks5_password):
    print("SOCKS5 代理可用！")
else:
    print("SOCKS5 代理不可用！")
