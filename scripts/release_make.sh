#! /bin/bash
# A modification of Dean Clatworthy's deploy script at: https://github.com/deanc/wordpress-plugin-git-svn
# The difference is that this script lives in the plugin's git repo & doesn't require an existing SVN repo.

# default paths
SCRIPTDIR="$( cd -- "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"
PLUGINDIR="$( cd -- "$(dirname "$SCRIPTDIR")" >/dev/null 2>&1 ; pwd -P )"
PLUGINSDIR="$( cd -- "$(dirname "$PLUGINDIR")" >/dev/null 2>&1 ; pwd -P )"
PLUGINSLUG=$(basename $PLUGINDIR)
MAINFILE="$PLUGINSLUG.php"

# svn config
SVNTMP="/tmp/$PLUGINSLUG-tmp"
SVNPATH="/tmp/$PLUGINSLUG"                             # path to a temp SVN repo. No trailing slash required and don't add trunk.
SVNURL="http://plugins.svn.wordpress.org/$PLUGINSLUG/" # Remote SVN repo on wordpress.org, with no trailing slash
rm -rf $SVNPATH
rm -rf $SVNTMP

# Let's begin...
echo ".........................................."
echo
echo "Preparing to deploy wordpress plugin"
echo
echo ".........................................."
echo

bash "$SCRIPTDIR/release_prepare.sh"
if [[ ! -f "$PLUGINSDIR/$PLUGINSLUG.zip" ]]; then
  echo "Release archive not found. Exiting..."
  exit
fi

# Check version in readme.txt is the same as plugin file
NEWVERSION1=$(grep "^Stable tag:" $PLUGINDIR/readme.txt | awk '{print $NF}')
echo "readme version: $NEWVERSION1"
NEWVERSION2=$(grep "Version:" $PLUGINDIR/$MAINFILE | awk '{print $NF}')
echo "$MAINFILE version: $NEWVERSION2"

if [ "$NEWVERSION1" != "$NEWVERSION2" ]; then
  echo "Versions don't match. Exiting...."
  exit 1
else
  echo "Versions match in README.txt and PHP file. Let's proceed..."
fi

cd $PLUGINDIR
if [ ! $(git tag -l "$NEWVERSION1") ]; then
  echo -e "Enter a commit message for this new version: \c"
  read COMMITMSG
  git commit -am "$COMMITMSG"

  echo "Tagging new version in git"
  git tag -a "$NEWVERSION1" -m "Tagging version $NEWVERSION1"

  echo "Pushing latest commit to origin, with tags"
  git push origin master
  git push origin master --tags
else
  echo "Git tag already exists. Skipping"
fi

echo "Creating local copy of SVN repo ..."
svn co $SVNURL $SVNPATH

# If SVN tag does not exists, create it.
if [ ! -d "$SVNPATH/tags/$NEWVERSION1" ]; then

  echo "Changing directory to SVN and committing to trunk"
  cd $SVNPATH/trunk/

  # re-construct PLUGINSLUG dir
  echo "Copying latest version to SVN trunk"
  unzip "$PLUGINSDIR/$PLUGINSLUG.zip" -d "$SVNTMP"
  cp -Rp "$SVNTMP/"* ./
  rm -rf $SVNTMP

  # Update all the files that are not set to be ignored
  echo -e "Enter a SVN username: \c"
  read SVNUSER
  svn status | grep -v "^.[ \t]*\..*" | grep "^\!" | awk '{print $2}' | xargs svn del
  svn status | grep -v "^.[ \t]*\..*" | grep "^?"  | awk '{print $2}' | xargs svn add
  svn commit --username=$SVNUSER -m "$COMMITMSG"

  echo "Creating new SVN tag & committing it"
  cd $SVNPATH

  # Copy and release new version
  svn copy trunk/ tags/$NEWVERSION1/
  cd $SVNPATH/tags/$NEWVERSION1
  svn commit --username=$SVNUSER -m "Tagging version $NEWVERSION1"

else
  echo "SVN tag already exists. Skipping"
fi

echo "Removing temporary directory $SVNPATH"
rm -fr $SVNPATH/

echo "New version published"
