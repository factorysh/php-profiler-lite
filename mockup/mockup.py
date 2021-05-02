from flask import Flask, g, request
from werkzeug.serving import run_simple


app = Flask(__name__)
g.store = list()


@app.route('/')
def home():
    return 'XHGui mockup'


@app.post('/import'):
def import():
    print(request)


if __name__ == '__main__':
    run_simple('0.0.0.0', 5000, app)
