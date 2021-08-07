/* 
 * Formula Game Setup React App
 */
import { Tooltip } from './module/tooltip.js';
import { SetupPlayersCarsPanel } from './formula/setupPlayersCarsPanel.js';
import { SetupGameParamsPanel } from './formula/setupGameParamsPanel.js';

class Setup extends React.Component {
  gameParams = {};
  gameParamsTimeout;
  gameParamsTimeoutMiliseconds = 2000;
  refreshIntervalMiliseconds = 2000;

  constructor(props) {
    super(props);
    this.state = {};
    this.update = this.update.bind(this);
    this.updateData = this.updateData.bind(this);
    this.updateGameParams = this.updateGameParams.bind(this);
    this.sendGameParams = this.sendGameParams.bind(this);
    this.sendUpdateDamage = this.sendUpdateDamage.bind(this);
    this.sendUpdatePlayerReady = this.sendUpdatePlayerReady.bind(this);
    this.startGame = this.startGame.bind(this);
    this.update();
    setInterval(this.update, this.refreshIntervalMiliseconds);
  }

  updateData(data) {
    console.log(JSON.stringify(Object.keys(data)));

    if (data["has_started"]) {
      window.location.href = 'formula/getBoard/' + this.props.id;
    }

    if (data.redirect) {
      window.location.href = data.target + "?redirect=" + encodeURI('/formula/get_waiting_room/') + this.props.id;
    }

    let state = {
      name: data.name,
      users: data.users
    };
    let game = data;
    delete game.users;

    if (this.state.game == null || !this.state.game.editable) {
      state.game = game;
    }

    this.setState(state);
  }

  update() {
    let url = 'formula/getSetupUpdateJson/' + this.props.id;
    $.getJSON(url, this.updateData);
  }

  updateGameParams(gameParams) {
    Object.assign(this.gameParams, gameParams);
    Object.assign(this.state.game, gameParams);
    this.setState({
      game: this.state.game
    });

    if (this.gameParamsTimeout == null) {
      this.gameParamsTimeout = setTimeout(this.sendGameParams, this.gameParamsTimeoutMiliseconds);
    }

    console.log("new params: " + JSON.stringify(gameParams));
    console.log(JSON.stringify(this.gameParams));
  }

  sendUpdateDamage(damageId, wearPoints) {
    let url = 'formula/editDamage/' + this.props.id;
    let payload = {
      _csrfToken: csrfToken,
      damage_id: damageId,
      wear_points: wearPoints
    };
    $.post(url, payload, null, 'json');
  }

  sendGameParams() {
    let url = 'formula/editSetup/' + this.props.id;
    let payload = this.gameParams;
    this.gameParamsTimeout = null;
    payload["_csrfToken"] = csrfToken;
    $.post(url, payload, null, 'json').fail(() => this.updateGameParams({}));
  }

  sendUpdatePlayerReady(ready) {
    let url = 'formula/setUserReady/' + this.props.id;
    let payload = {
      gameId: this.props.id,
      ready: ready,
      _csrfToken: csrfToken
    };
    $.post(url, payload, null, 'json');
    this.state.users.find(user => user.editable).ready_state = ready;
    this.setState({
      users: this.state.users
    });
  }

  startGame() {
    let url = 'formula/start/' + this.props.id;
    let payload = {
      _csrfToken: csrfToken
    };
    $.post(url, payload, null, 'json');
  }

  render() {
    if (this.state.game == null) {
      return null;
    } else {
      return /*#__PURE__*/React.createElement("div", {
        id: "setup"
      }, /*#__PURE__*/React.createElement("h2", {
        id: "game-name"
      }, this.state.name), /*#__PURE__*/React.createElement("div", {
        className: "row"
      }, /*#__PURE__*/React.createElement("div", {
        id: "player-car-column"
      }, /*#__PURE__*/React.createElement(SetupPlayersCarsPanel, {
        users: this.state.users,
        totalWP: this.state.game.wear_points,
        onDamageChange: this.sendUpdateDamage,
        onPlayerReadyChange: this.sendUpdatePlayerReady
      })), /*#__PURE__*/React.createElement("div", {
        id: "setup-column"
      }, /*#__PURE__*/React.createElement(SetupGameParamsPanel, {
        game: this.state.game,
        onUpdate: this.updateGameParams,
        editable: this.state.game.editable,
        playersReady: this.state.users.every(user => user.ready_state),
        onStart: this.startGame
      }))));
    }
  }

}

ReactDOM.render( /*#__PURE__*/React.createElement(Setup, {
  id: id
}), document.getElementById('root'));