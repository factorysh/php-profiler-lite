from flask import Flask, request
from werkzeug.serving import run_simple


app = Flask(__name__)
data = dict()


@app.route("/")
def home():
    return "XHGui mockup"


@app.route("/dump")
def dump():
    return data["upload"]


@app.route("/upload", methods=["POST"])
def upload():
    app.logger.debug(request.form)
    app.logger.debug(request.headers)
    app.logger.debug(request.get_json())
    data["upload"] = request.get_json()
    return "ok"


if __name__ == "__main__":
    app.run("0.0.0.0", 5000, debug=True)
