class GameIndex extends React.Component {
  intervalMs = 5000;

  constructor(props) {
    super(props);
    this.state = {};
    this.loadGameList = this.loadGameList.bind(this);
    this.updateGameList = this.updateGameList.bind(this);
    this.loadGameList();
    setInterval(this.loadGameList, this.intervalMs);
  }

  updateGameList(data) {
    this.setState({
      games: data
    });
    console.log(JSON.stringify(data));
  }

  loadGameList() {
    let url = 'formula/index/';
    $.getJSON(url, this.updateGameList);
  }

  render = () => /*#__PURE__*/React.createElement("div", {
    className: "games index content"
  }, /*#__PURE__*/React.createElement("button", {
    className: "button float-right"
  }, "New Game"), /*#__PURE__*/React.createElement("h3", null, "Games"), /*#__PURE__*/React.createElement("div", {
    className: "table-responsive"
  }, /*#__PURE__*/React.createElement("table", null, /*#__PURE__*/React.createElement("thead", null, /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("th", null, "Name"), /*#__PURE__*/React.createElement("th", {
    className: "actions"
  }, "Actions"))), /*#__PURE__*/React.createElement("tbody", null, this.state.games?.map(game => /*#__PURE__*/React.createElement("tr", {
    key: game.id
  }, /*#__PURE__*/React.createElement("td", null, game.name), /*#__PURE__*/React.createElement("td", {
    className: "actions"
  }, /*#__PURE__*/React.createElement("a", {
    onClick: () => alert(game.id)
  }, "Join"))))))));
}

ReactDOM.render( /*#__PURE__*/React.createElement(GameIndex, null), document.getElementById('root'));