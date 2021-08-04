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

        _this.refreshInterval = 2000;

        _this.state = {};
        _this.update = _this.update.bind(_this);
        _this.updateData = _this.updateData.bind(_this);
        _this.update();
        return _this;
    }

    _createClass(Setup, [{
        key: 'updateData',
        value: function updateData(data) {
            console.log(JSON.stringify(data));
            this.setState({ data: data });
        }
    }, {
        key: 'update',
        value: function update() {
            var url = 'formula/getSetupUpdateJson/' + this.props.id;
            $.getJSON(url, this.updateData);
        }
    }, {
        key: 'render',
        value: function render() {
            if (this.state.data == null) {
                return null;
            } else {
                return React.createElement(
                    'div',
                    { id: 'setup' },
                    React.createElement(
                        'h2',
                        { id: 'game-name' },
                        this.state.data.name
                    ),
                    React.createElement(
                        'div',
                        { className: 'row' },
                        React.createElement(
                            'div',
                            { id: 'player-car-column' },
                            React.createElement(SetupPlayersCarsPanel, { users: this.state.data.users })
                        ),
                        React.createElement(
                            'div',
                            { id: 'setup-column' },
                            React.createElement(SetupGameParamsPanel, { game: this.state.data })
                        )
                    )
                );
            }
        }
    }]);

    return Setup;
}(React.Component);

ReactDOM.render(React.createElement(Setup, { id: id }), document.getElementById('root'));