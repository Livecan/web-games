var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/* 
 * Formula Game Setup React App
 */

import { Tooltip } from './module/tooltip.js';
import { SetupPlayersCarsPanel } from './formula/setupPlayersCarsPanel.js';
import { SetupGameParamsPanel } from './formula/setupGameParamsPanel.js';

var Setup = function (_React$Component) {
    _inherits(Setup, _React$Component);

    function Setup(props) {
        _classCallCheck(this, Setup);

        var _this = _possibleConstructorReturn(this, (Setup.__proto__ || Object.getPrototypeOf(Setup)).call(this, props));

        _this.gameParams = {};
        _this.gameParamsTimeoutMiliseconds = 2000;
        _this.refreshIntervalMiliseconds = 2000;

        _this.state = {};
        _this.update = _this.update.bind(_this);
        _this.updateData = _this.updateData.bind(_this);
        _this.updateGameParams = _this.updateGameParams.bind(_this);
        _this.sendGameParams = _this.sendGameParams.bind(_this);
        _this.sendUpdateDamage = _this.sendUpdateDamage.bind(_this);
        _this.sendUpdatePlayerReady = _this.sendUpdatePlayerReady.bind(_this);
        _this.startGame = _this.startGame.bind(_this);
        _this.update();
        setInterval(_this.update, _this.refreshIntervalMiliseconds);
        return _this;
    }

    _createClass(Setup, [{
        key: 'updateData',
        value: function updateData(data) {
            console.log(JSON.stringify(Object.keys(data)));
            if (data["has_started"]) {
                window.location.href = 'formula/getBoard/' + this.props.id;
            }
            var state = {
                name: data.name,
                users: data.users
            };
            var game = data;
            delete game.users;
            if (this.state.game == null || !this.state.game.editable) {
                state.game = game;
            }
            this.setState(state);
        }
    }, {
        key: 'update',
        value: function update() {
            var url = 'formula/getSetupUpdateJson/' + this.props.id;
            $.getJSON(url, this.updateData);
        }
    }, {
        key: 'updateGameParams',
        value: function updateGameParams(gameParams) {
            Object.assign(this.gameParams, gameParams);
            Object.assign(this.state.game, gameParams);
            this.setState({ game: this.state.game });
            if (this.gameParamsTimeout == null) {
                this.gameParamsTimeout = setTimeout(this.sendGameParams, this.gameParamsTimeoutMiliseconds);
            }
            console.log("new params: " + JSON.stringify(gameParams));
            console.log(JSON.stringify(this.gameParams));
        }
    }, {
        key: 'sendUpdateDamage',
        value: function sendUpdateDamage(damageId, wearPoints) {
            var url = 'formula/editDamage/' + this.props.id;
            var payload = { _csrfToken: csrfToken, damage_id: damageId, wear_points: wearPoints };
            $.post(url, payload, null, 'json');
        }
    }, {
        key: 'sendGameParams',
        value: function sendGameParams() {
            var _this2 = this;

            var url = 'formula/editSetup/' + this.props.id;
            var payload = this.gameParams;
            this.gameParamsTimeout = null;
            payload["_csrfToken"] = csrfToken;
            $.post(url, payload, null, 'json').fail(function () {
                return _this2.updateGameParams({});
            });
        }
    }, {
        key: 'sendUpdatePlayerReady',
        value: function sendUpdatePlayerReady(ready) {
            var url = 'formula/setUserReady/' + this.props.id;
            var payload = {
                gameId: this.props.id,
                ready: ready,
                _csrfToken: csrfToken
            };
            $.post(url, payload, null, 'json');
            this.state.users.find(function (user) {
                return user.editable;
            }).ready_state = ready;
            this.setState({ users: this.state.users });
        }
    }, {
        key: 'startGame',
        value: function startGame() {
            var url = 'formula/start/' + this.props.id;
            var payload = { _csrfToken: csrfToken };
            $.post(url, payload, null, 'json');
        }
    }, {
        key: 'render',
        value: function render() {
            if (this.state.game == null) {
                return null;
            } else {
                return React.createElement(
                    'div',
                    { id: 'setup' },
                    React.createElement(
                        'h2',
                        { id: 'game-name' },
                        this.state.name
                    ),
                    React.createElement(
                        'div',
                        { className: 'row' },
                        React.createElement(
                            'div',
                            { id: 'player-car-column' },
                            React.createElement(SetupPlayersCarsPanel, { users: this.state.users,
                                totalWP: this.state.game.wear_points,
                                onDamageChange: this.sendUpdateDamage,
                                onPlayerReadyChange: this.sendUpdatePlayerReady })
                        ),
                        React.createElement(
                            'div',
                            { id: 'setup-column' },
                            React.createElement(SetupGameParamsPanel, { game: this.state.game,
                                onUpdate: this.updateGameParams,
                                editable: this.state.game.editable,
                                playersReady: this.state.users.every(function (user) {
                                    return user.ready_state;
                                }),
                                onStart: this.startGame })
                        )
                    )
                );
            }
        }
    }]);

    return Setup;
}(React.Component);

ReactDOM.render(React.createElement(Setup, { id: id }), document.getElementById('root'));