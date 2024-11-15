import pycurl #pip3 install pycurl
from io import BytesIO

def test_socks5(host, port, username, password):
    buffer = BytesIO()
    c = pycurl.Curl()
    
    # 设置代理
    c.setopt(pycurl.PROXY, host)
    c.setopt(pycurl.PROXYPORT, port)
    c.setopt(pycurl.PROXYTYPE, pycurl.PROXYTYPE_SOCKS5)
    c.setopt(pycurl.PROXYUSERPWD, f'{username}:{password}')
    
    # 设置请求
    c.setopt(pycurl.URL, 'http://httpbin.org/ip')
    c.setopt(pycurl.WRITEDATA, buffer)
    
    try:
        c.perform()
        body = buffer.getvalue().decode('utf-8')
        print(f"代理返回结果: {body}")
        return True
    except Exception as e:
        print(f"连接失败：{e}")
        return False
    finally:
        c.close()

# 测试代理
socks5_host = '203.6.230.110'
socks5_port = 20404
socks5_username = 'user112'
socks5_password = '078Su4'

if test_socks5(socks5_host, socks5_port, socks5_username, socks5_password):
    print("SOCKS5 代理可用！")
else:
    print("SOCKS5 代理不可用！")
