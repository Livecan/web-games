import { Sprite } from './sprite.js';
import { carSprites } from './variables.js';
import { DamagePanel } from './damagePanel.js';
export class CarDamagePanel extends React.Component {
  compare(a, b) {
    let lastOrder = 999;

    if (a.state == b.state) {
      if (a.state == "R" && (a.order ?? lastOrder) < (b.order ?? lastOrder)) {
        //both racing - compare order
        return -1;
      }

      if (a.state == "F" && a.ranking < b.ranking) {
        //both finished - compare ranking
        return -1;
      }

      if (a.state == "X" && a.id < b.id) {
        //if both out, just make sure that there is a given order
        return -1;
      }

      return 1;
    }

    if (a.state == "R") {
      //only A is racing
      return -1;
    }

    if (b.state == "R") {
      //only B is racing
      return 1;
    }

    if (a.state == "F") {
      //A is racing and B retired
      return -1;
    }

    if (b.state == "F") {
      //B is racing and A retired
      return 1;
    }

    return 1;
  }

  render() {
    return /*#__PURE__*/React.createElement("table", {
      id: "car_stats_table",
      className: "damage_table"
    }, /*#__PURE__*/React.createElement("tbody", null, this.props.cars.sort(this.compare).map(car => /*#__PURE__*/React.createElement("tr", {
      key: car.index
    }, /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement(Sprite, {
      src: "img/formula/cars/" + carSprites[car.index],
      className: "car_img",
      key: car.id,
      width: "20",
      height: "50",
      unit: "px"
    })), /*#__PURE__*/React.createElement("td", null, {
      'R':
      /*#__PURE__*/
      //racing - display current gear
      React.createElement(Sprite, {
        src: "img/formula/gears/" + Math.max(1, car.gear || 1) + ".svg",
        className: "state",
        width: "20px",
        height: "20px"
      }),
      'F':
      /*#__PURE__*/
      //finished - display ranking
      React.createElement("span", {
        className: "ranking"
      }, /*#__PURE__*/React.createElement(Sprite, {
        src: "img/formula/gears/finish.svg",
        className: "finish_img",
        width: "20px",
        height: "20px"
      }), /*#__PURE__*/React.createElement("span", null, car.ranking)),
      'X':
      /*#__PURE__*/
      //retired - display X icon
      React.createElement(Sprite, {
        src: "img/formula/gears/out.svg",
        className: "state",
        width: "20px",
        height: "20px"
      })
    }[car.state]), /*#__PURE__*/React.createElement("td", null, this.props.users.find(user => user.id == car.user_id).name), /*#__PURE__*/React.createElement(DamagePanel, {
      damages: car.fo_damages
    })))));
  }

}