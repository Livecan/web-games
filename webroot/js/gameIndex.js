class GameIndex extends React.Component {
  intervalMs = 5000;
  redirectDelay = 10;

  constructor(props) {
    super(props);
    this.state = {};
    this.getGameList = this.getGameList.bind(this);
    this.updateGameList = this.updateGameList.bind(this);
    this.postCreateNewGame = this.postCreateNewGame.bind(this);
    this.postJoinGame = this.postJoinGame.bind(this);
    this.getGameList();
    setInterval(this.getGameList, this.intervalMs);
  }

  updateGameList(data) {
    this.setState({
      games: data
    });
    console.log(JSON.stringify(data));
  }

  getGameList() {
    let url = 'formula/index/';
    $.getJSON(url, this.updateGameList);
  }

  postCreateNewGame() {
    let url = 'formula/create-new-game/';
    let payload = {
      _csrfToken: csrfToken
    };
    $.post(url, payload, data => this.redirectWaitingRoom(data.id), "json");
  }

  postJoinGame(id) {
    let url = 'formula/join-game/' + id;
    let payload = {
      _csrfToken: csrfToken,
      game_id: id
    };
    $.post(url, payload, this.redirectWaitingRoom(id), "json");
  }

  redirectWaitingRoom(id) {
    //delay to avoid unauthorized access because of uncommited transactions on the server
    setTimeout(() => window.location.href = "formula/get-waiting-room/" + id, this.redirectDelay);
  }

  render = () => /*#__PURE__*/React.createElement("div", {
    className: "games index content"
  }, /*#__PURE__*/React.createElement("button", {
    className: "button float-right",
    onClick: this.postCreateNewGame
  }, "New Game"), /*#__PURE__*/React.createElement("h3", null, "Games"), /*#__PURE__*/React.createElement("div", {
    className: "table-responsive"
  }, /*#__PURE__*/React.createElement("table", null, /*#__PURE__*/React.createElement("thead", null, /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("th", null, "Name"), /*#__PURE__*/React.createElement("th", null, "Players"), /*#__PURE__*/React.createElement("th", {
    className: "actions"
  }, "Actions"))), /*#__PURE__*/React.createElement("tbody", null, this.state.games?.map(game => /*#__PURE__*/React.createElement("tr", {
    key: game.id
  }, /*#__PURE__*/React.createElement("td", null, game.name), /*#__PURE__*/React.createElement("td", null, game.users.length), /*#__PURE__*/React.createElement("td", {
    className: "actions"
  }, /*#__PURE__*/React.createElement("a", {
    onClick: () => this.postJoinGame(game.id)
  }, "Join"))))))));
}

ReactDOM.render( /*#__PURE__*/React.createElement(GameIndex, null), document.getElementById('root'));