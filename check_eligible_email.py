#检测是否有符合条件发件人的邮箱
import imapclient
import pyzmail36

# 读取email.txt文件，格式为 邮箱 密码
with open("email.txt", "r") as f:
   emails = f.readlines()

# 提取账号和密码
accounts = []
for email in emails:
   username, password = email.strip().split(" ")
   accounts.append((username, password))

# 连接到IMAP服务器并登录
imap_server = "outlook.office365.com"
mail = imapclient.IMAPClient(imap_server, ssl=True)
mail.login(username, password)

# 遍历所有账户并搜索支持邮件
for username, password in accounts:
   mail.select_folder("INBOX")
   uids = mail.search(['FROM', 'support@info.cradles.io'])
   if uids:
       print(f"{username} 包含支持邮件")
   else:
       print(f"{username} 不包含支持邮件")

   # 断开IMAP连接
   mail.logout()