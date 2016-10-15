import React from 'react';
import {AppBar, Drawer, MenuItem, Tabs, Tab, Card, CardActions, CardHeader, CardMedia, CardTitle, CardText} from 'material-ui';
import {AvMovie, EditorInsertChart, ActionSettings} from 'material-ui/svg-icons';
import {Doughnut, Bar} from 'react-chartjs';

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
            data: [65, 59, 80, 81, 56, 55]
          },
          {
            label: "Male",
            fillColor: "rgba(151,187,205,0.2)",
            data: [65, 59, 80, 81, 56, 55]
          }
        ]
      },
      data: [
        {
          value: 200,
          color: 'Pink',
          label: 'Female'
        },
        {
          value: 4381,
          color: 'Cyan',
          label: 'Male'
        }
      ]
    };
  }

  handleToggle = () => this.setState({open: !this.state.open});

  toggleTab = (value) => this.setState({tab: value});

  componentDidMount () {
    setInterval(() => {
      fetch("http://121.201.8.23:8088/image/get").then((result) => {
        return result.json();
      }).then((data) => {
        let updatedData = [
          {
            value: 0,
            color: 'Pink',
            label: 'Female'
          },
          {
            value: 0,
            color: 'Cyan',
            label: 'Male'
          }
        ];

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
            updatedData[0].value += 1;
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
            updatedData[1].value += 1;
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
                  <Doughnut data={this.state.data} width="600" height="250"/>
                </CardText>
                <CardActions>
                </CardActions>
              </Card>
              <Card>
                <CardHeader
                  title="Age Distribution"
                />
                <CardText
                  style={{textAlign: 'center'}}
                >
                  <Bar data={this.state.barChartData} width="600" height="250"/>
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
