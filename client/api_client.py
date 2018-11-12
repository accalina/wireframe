
import requests as rq

def main():
    banner()
    generate_token()

def generate_token():
    print("//generate token")
    username = input("Please specify username: ")
    url = 'http://localhost/accalina/wireframe/pos_wireframe/api_wolf/genToken'
    payload = {'username': username}
    send(url, payload)


def revoke_token():
    print("//revoke token")
    username = input("Please specify username: ")
    url = 'http://localhost/accalina/wireframe/pos_wireframe/api_wolf/revokeToken'
    payload = {'username': username}
    send(url, payload)


def escalate_user():
    print("//escalate user")
    username = input("Please specify username: ")
    url = 'http://localhost/accalina/wireframe/pos_wireframe/api_wolf/userEscalate'
    payload = {'username': username}
    send(url, payload)


def descalate_user():
    print("//descalate user")
    username = input("Please specify username: ")
    url = 'http://localhost/accalina/wireframe/pos_wireframe/api_wolf/userDescalate'
    payload = {'username': username}
    send(url, payload)

def create_user():
    print("//create user")
    url = 'http://localhost/accalina/wireframe/pos_wireframe/api_wolf/register'
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

if __name__ == '__main__':
    main()
