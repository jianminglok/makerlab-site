/**
 * Feed component home page.
 */

import React, { Component } from 'react';
import PropTypes from 'prop-types'

import withStyles from 'material-ui/styles/withStyles';
import Grid from 'material-ui/Grid';
import Card, { CardHeader, CardContent, CardMedia, CardActions } from 'material-ui/Card';
import Typography from 'material-ui/Typography';
import Button from 'material-ui/Button';
import IconButton from 'material-ui/IconButton';
import { blue } from 'material-ui/colors';
import { Link } from 'react-router-dom';

import FavoriteIcon from 'material-ui-icons/Favorite';
import Comment from 'material-ui-icons/Comment';
import RssFeed from 'material-ui-icons/RssFeed';

import request, { createRequestURI } from '../utils/request';


const styles = (theme:object) => ({
  root: {
    padding: theme.spacing.unit,
    width: '100%',
    margin: 0
  },
  flexGrow: {},
  link: {
    color: blue[400],
    textDecoration: 'none'
  }
});

class Home extends Component
{
  static propTypes = {
    classes: PropTypes.object.isRequired
  };

  state = {
    feeds: 0,
    comments: 0
  };

  componentDidMount () {
    request.get(createRequestURI('statistics'), {
      validataStatus: status => status === 200
    }).then(({ data } = {} ) => {
      this.setState({
        feeds: data.feedsCount || 0,
        comments: data.commentsCount || 0
      })
    }).catch( error => {

    });
  };

  render() {
    const { classes } = this.props;
    const { feeds = 0, comments = 0 } = this.state;

    return (
      <div>
        <Grid container className={classes.root}>
          <Grid item xs={12} sm={6}>
            <Card>
              <CardContent>
                <Typography type="headline" component="h2">
                  动态统计
                </Typography>
                <Typography component="p">
                  系统中所有动态的统计数据(不包括已删除的动态)
                </Typography>
              </CardContent>

              <CardActions>
                <Button disabled>
                  <RssFeed color={blue[400]} />&nbsp;{feeds}
                </Button>

                <div className={classes.flexGrow} />

                <Button dense color="primary">
                  <Link to="/feeds" className={classes.link}>管理动态</Link>
                </Button>

              </CardActions>

            </Card>
          </Grid>
          <Grid item xs={12} sm={6}>
            <Card>
              <CardContent>
                <Typography type="headline" component="h2">
                  动态评论统计
                </Typography>
                <Typography component="p">
                  系统中所有动态评论的统计数据(不包括已删除的评论)
                </Typography>
              </CardContent>

              <CardActions>
                <Button disabled>
                  <Comment color={blue[400]} />&nbsp;{comments}
                </Button>

                <div className={classes.flexGrow} />

                <Button dense color="primary">
                  <Link to="/comments" className={classes.link}>管理评论</Link>
                </Button>

              </CardActions>

            </Card>
          </Grid>
          <Grid item xs={12} sm={6}>
            <Card>
              <CardContent>
                <Typography type="headline" component="h2">
                  动态回收站
                </Typography>
                <Typography component="p">
                  所有被放入回收站的动态管理
                </Typography>
              </CardContent>

              <CardActions>
                <Button dense color="primary">
                  <Link to="/deleteFeeds" className={classes.link}>管理动态回收站</Link>
                </Button>

              </CardActions>

            </Card>
          </Grid>
          {/*<Grid item xs={12} sm={6}>
            <Card>
              <CardContent>
                <Typography type="headline" component="h2">
                  动态评论回收站
                </Typography>
                <Typography component="p">
                  被加入回收站的动态评论
                </Typography>
              </CardContent>

              <CardActions>
                <Button dense color="primary">
                  <Link to="/deleteComments" className={classes.link}>管理评论回收站</Link>
                </Button>

              </CardActions>

            </Card>
          </Grid>*/}
        </Grid>
      </div>
    );
  }
}

export default withStyles(styles)(Home);
