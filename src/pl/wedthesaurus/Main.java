package pl.wedthesaurus;

import java.sql.ResultSet;

public class Main
{
	private final static String BASH_SCRIPT = "script.sh";
	private final static String PYTHON_SCRIPT = "script.py";
	private final static String SELECT_TEXTS = "select text from texts;";
	private final static String TEXT_FILE_NAME = "text.txt";
	private final static String WORDS_LIST_FILE_NAME = "words.txt";
	private final static String SCRIPT_RESULT_FILE_NAME = "result.txt";
	
	public static void main(String[] args) throws Exception
	{
		MyConnection selectConnection = new MyConnection();
		WriteToFile writeToFile = new WriteToFile();
		ResultSet resultSet = selectConnection.executeQuery(SELECT_TEXTS);
		while (resultSet.next())
		{
			String text = resultSet.getString("text");
			System.out.println(text); //only for debug
			writeToFile.write(text, TEXT_FILE_NAME);
			Process proc = new ProcessBuilder(BASH_SCRIPT, TEXT_FILE_NAME).start();
			proc.waitFor();
		}
		selectConnection.close();
		Process proc = new ProcessBuilder(PYTHON_SCRIPT, WORDS_LIST_FILE_NAME).start();
		proc.waitFor();
		new WriteToDB().write(SCRIPT_RESULT_FILE_NAME);
	}

}
