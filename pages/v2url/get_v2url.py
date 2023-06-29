import requests
import random
import string
import re

class CKCloudAPI:
    def __init__(self, email=None, password=None):
        self.base_url = "https://www.ckcloud.shop/api/v1"
        self.token = None
        self.email = email
        self.password = password

    def register(self):
        url = f"{self.base_url}/passport/auth/register"
        data = {"email": self.email, "password": self.password}
        try:
            response = requests.post(url, data=data)
            response.raise_for_status()
            print("注册成功")
        except requests.exceptions.HTTPError as e:
            error_msg = e.response.json()["message"]
            print(f"注册失败，错误信息为：{error_msg}")
            input("")

    def login(self):
        url = f"{self.base_url}/passport/auth/login"
        data = {"email": self.email, "password": self.password}
        try:
            response = requests.post(url, data=data)
            response.raise_for_status()
            print("登录成功")
            self.token = response.json()["data"]["auth_data"]
            token = response.json()["data"]["token"]
            print("Token:"+token)
        except requests.exceptions.HTTPError as e:
            error_msg = e.response.json()["message"]
            print(f"登录失败，错误信息为：{error_msg}")
            self.token = None
            input("")

    def get_link(self, node=None):
        if node:
            url = f"{self.base_url}/user/getSubscribe?node_id={node}"
        else:
            url = f"{self.base_url}/user/getSubscribe"
        headers = {"Authorization": self.token}
        try:
            response = requests.get(url, headers=headers)
            response.raise_for_status()
            return response.json()["data"]["subscribe_url"]
        except requests.exceptions.HTTPError as e:
            error_msg = e.response.json()["message"]
            print(f"获取链接失败，错误信息为：{error_msg}")
            input("")
            return None
            


def generate_email():
    domains = [
        "gmail.com",
        "qq.com",
        "163.com",
        "yahoo.com",
        "sina.com",
        "126.com",
        "outlook.com",
        "yeah.net",
        "foxmail.com",
        "hotmail.com",
    ]
    letters = string.ascii_lowercase
    username = "".join(random.choice(letters) for i in range(8))
    domain = random.choice(domains)
    return f"{username}@{domain}"


def generate_password():
    chars = string.ascii_letters + string.digits + string.punctuation
    password = "".join(random.choice(chars) for i in range(12))
    return password


if __name__ == "__main__":
    api = CKCloudAPI()
    email = generate_email()
    password = generate_password()
    print(f"Email: {email}")
    print(f"Password: {password}")
    api.email = email
    api.password = password
    api.register()
    api.login()
    if api.token:
        print("获取链接成功")
        link = api.get_link()
        if link:
            print(link)
    else:
        print("获取链接失败")
input("")
