package pl.wedthesaurus;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class MyConnection
{
    private final static String DBURL = "jdbc:mysql://127.0.0.1:3306/WEDT";
    private final static String DBUSER = "user";
    private final static String DBPASS = "pass";
    private final static String DBDRIVER = "com.mysql.jdbc.Driver";
 
    private Connection connection;
    private Statement statement;
 
    public MyConnection() throws Exception
    {
    	Class.forName(DBDRIVER).newInstance();
    	connection = DriverManager.getConnection(DBURL, DBUSER, DBPASS);
    }
 
    public void executeUpdate(String query) throws SQLException
    {
    	statement = connection.createStatement();
    	statement.executeUpdate(query);
    	statement.close();
    }
    
    public ResultSet executeQuery(String query) throws SQLException
    {
    	statement = connection.createStatement();
    	return statement.executeQuery(query);
    }
    
    public void close() throws SQLException 
    {
    	statement.close();
    	connection.close();
    }
}


