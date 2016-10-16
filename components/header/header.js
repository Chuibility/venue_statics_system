import React from 'react';
import {AppBar, Drawer, MenuItem, Tabs, Tab, Card, CardActions, CardHeader, CardMedia, CardTitle, CardText} from 'material-ui';
import {AvMovie, EditorInsertChart, ActionSettings} from 'material-ui/svg-icons';
import {Doughnut, Bar, Pie} from 'react-chartjs-2';
import { defaults } from 'react-chartjs-2';

// Disable animating charts by default.
//defaults.global.animation = false;

const inkBarStyle = {
  display: 'none'
};

const paperStyle = {
  height: "400px",
  width: "80%",
  margin: 'auto',
  textAlign: 'center',
  display: 'inline-block',
};

const options = {
  tooltips: {
    mode: 'label'
  },
  legend: {
    labels: {
      fontSize: 20
    }
  }
}

export default class Header extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      open: false,
      tab: 'statistics',
      barChartData: {
        labels: ["13-16", "17-20", "21-24", "25-28", "31-34", "35-38"],
        datasets: [
          {
            label: "Female",
            fillColor: "rgba(220,220,220,0.2)",
            data: [0, 0, 0, 0, 0, 0]
          },
          {
            label: "Male",
            fillColor: "rgba(151,187,205,0.2)",
            data: [0, 0, 0, 0, 0, 0]
          }
        ]
      },
      data: {
        labels: ['Female', 'Male'],
        datasets: [
          {
            data: [0, 0],
            backgroundColor: [
              '#FFC0CB', '#00FFFF'
            ]
          }
        ]
      },
      smileData: {
        labels: ['😄', '😐', '🙁'],
        datasets: [
          {
            data: [0, 0, 0],
            backgroundColor: [
              '#00C851', '#ffbb33', '#ff4444'
            ]
          }
        ]
      }
    };
  }

  handleToggle = () => this.setState({open: !this.state.open});

  toggleTab = (value) => this.setState({tab: value});

  componentDidMount () {
    setInterval(() => {
      fetch("http://121.201.8.23:8088/image/smile").then((result) => {
        return result.json();
      }).then((data) => {
        let updatedData = {
          labels: ['😄', '😐', '🙁'],
          datasets: [
            {
              data: [0, 0, 0],
              backgroundColor: [
                '#00C851', '#ffbb33', '#ff4444'
              ]
            }
          ]
        };
        for (let datum of data) {
          if (datum < 0.33333) {
            updatedData.datasets[0].data[2] += 1;
          }
          else if (datum < 0.66667) {
            updatedData.datasets[0].data[1] += 1;
          }
          else {
            updatedData.datasets[0].data[0] += 1;
          }
        }

        this.setState({
          smileData: updatedData
        });
      });
      fetch("http://121.201.8.23:8088/image/get").then((result) => {
        return result.json();
      }).then((data) => {
        let updatedData = {
          labels: ['Female', 'Male'],
          datasets: [
            {
              data: [0, 0],
              backgroundColor: [
                '#FFC0CB', '#00FFFF'
              ]
            }
          ]
        };

        let updatedBarData = {
          labels: ["13-16", "17-20", "21-24", "25-28", "29-32", "33-36"],
          datasets: [
            {
              label: "Female",
              fillColor: "rgba(220,220,220,0.2)",
              data: [0, 0, 0, 0, 0, 0]
            },
            {
              label: "Male",
              fillColor: "rgba(151,187,205,0.2)",
              data: [0, 0, 0, 0, 0, 0]
            }
          ]
        };

        for (let datum of data) {
          if (datum.gender === 'female') {
            updatedData.datasets[0].data[0] += 1;
            if (datum.age < 16) {
              updatedBarData.datasets[0].data[0] += 1;
            }
            else if (datum.age < 20) {
              updatedBarData.datasets[0].data[1] += 1;
            }
            else if (datum.age < 24) {
              updatedBarData.datasets[0].data[2] += 1;
            }
            else if (datum.age < 28) {
              updatedBarData.datasets[0].data[3] += 1;
            }
            else if (datum.age < 32) {
              updatedBarData.datasets[0].data[4] += 1;
            }
            else if (datum.age < 36) {
              updatedBarData.datasets[0].data[5] += 1;
            }
          }
          else {
            updatedData.datasets[0].data[1] += 1;
            if (datum.age < 16) {
              updatedBarData.datasets[1].data[0] += 1;
            }
            else if (datum.age < 20) {
              updatedBarData.datasets[1].data[1] += 1;
            }
            else if (datum.age < 24) {
              updatedBarData.datasets[1].data[2] += 1;
            }
            else if (datum.age < 28) {
              updatedBarData.datasets[1].data[3] += 1;
            }
            else if (datum.age < 32) {
              updatedBarData.datasets[1].data[4] += 1;
            }
            else if (datum.age < 36) {
              updatedBarData.datasets[1].data[5] += 1;
            }
          }
        }

        this.setState({
          data: updatedData,
          barChartData: updatedBarData
        })

        console.log(updatedData);
      })
    }, 1000)
  }

  handleTabChange = (value) => {
    this.setState({
      tab: value
    });
  };

  render() {
    return (
      <div>
        <AppBar
          title="Hello World"
          onLeftIconButtonTouchTap={this.handleToggle}
        />
        <Drawer
          docked={false}
          width={200}
          open={this.state.open}
          onRequestChange={(open) => this.setState({open})}
        >
          <AppBar
            title="Menu"
            showMenuIconButton={false}
          />
          <MenuItem
            primaryText="Realtime Video"
            onTouchTap={() => this.toggleTab("video")}
            leftIcon={<AvMovie />}
          />
          <MenuItem
            primaryText="Statistics"
            onTouchTap={() => this.toggleTab("statistics")}
            leftIcon={<EditorInsertChart />}
          />
          <MenuItem
            primaryText="Settings"
            onTouchTap={() => this.toggleTab("settings")}
            leftIcon={<ActionSettings />}
          />
        </Drawer>
        <Tabs
          tabItemContainerStyle={inkBarStyle}
          value={this.state.tab}
        >
          <Tab
            value="video"
          >
            Video
          </Tab>
          <Tab
            value="statistics"
          >
            <div style={{margin: '50px 200px'}}>
              <Card style={{marginBottom: '50px'}}>
                <CardHeader
                  title="Gender Distribution"
                />
                <CardText
                  style={{textAlign: 'center'}}
                >
                  <Doughnut data={this.state.data} options={options} width={600} height={250} />
                </CardText>
                <CardActions>
                </CardActions>
              </Card>
              <Card style={{marginBottom: '50px'}}>
                <CardHeader
                  title="Age Distribution"
                />
                <CardText
                  style={{textAlign: 'center'}}
                >
                  <Bar data={this.state.barChartData} width={600} height={250} />
                </CardText>
                <CardActions>
                </CardActions>
              </Card>
              <Card>
                <CardHeader
                  title="Smile Index"
                />
                <CardText
                  style={{textAlign: 'center'}}
                >
                  <Pie data={this.state.smileData} options={options} width={600} height={250} />
                </CardText>
                <CardActions>
                </CardActions>
              </Card>
            </div>
          </Tab>
          <Tab
            value="settings"
          >
            Settings
          </Tab>
        </Tabs>
      </div>
    )
  }
};
