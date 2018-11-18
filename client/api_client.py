
import requests as rq

def main():
    banner()
    user_info()

def user_info():
    print("//user profile")
    url = 'http://localhost/accalina/wireframe/api/login'
    payload = {}
    see(url, payload)

def generate_token():
    print("//generate token")
    username = input("Please specify username: ")
    url = 'http://localhost/accalina/wireframe/api/genToken'
    payload = {'username': username}
    send(url, payload)


def revoke_token():
    print("//revoke token")
    username = input("Please specify username: ")
    url = 'http://localhost/accalina/wireframe/api/revokeToken'
    payload = {'username': username}
    send(url, payload)


def escalate_user():
    print("//escalate user")
    username = input("Please specify username: ")
    url = 'http://localhost/accalina/wireframe/api/userEscalate'
    payload = {'username': username}
    send(url, payload)


def descalate_user():
    print("//descalate user")
    username = input("Please specify username: ")
    url = 'http://localhost/accalina/wireframe/api/userDescalate'
    payload = {'username': username}
    send(url, payload)

def create_user():
    print("//create user")
    url = 'http://localhost/accalina/wireframe/api/register'
    payload = {'username': 'shirosachi', 'password': 'shirosachi'}
    send(url,payload)


def banner():
    print("========================")
    print("Wireframe API Controller")
    print("========================")
    print("")
    pass

def send(url,payload):
    result = rq.post(url,data=payload)
    print(result.status_code)
    print(result.text)

def see(url, payload):
    result = rq.get(url, data=payload)
    print(result.status_code)
    print(result.text)


if __name__ == '__main__':
    main()
