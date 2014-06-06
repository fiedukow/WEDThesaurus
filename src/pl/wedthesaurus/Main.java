package pl.wedthesaurus;

import java.sql.ResultSet;

public class Main
{
	private final static String BASH_SCRIPT = "./script.sh";
	private final static String PYTHON_SCRIPT = "getText.py";
	private final static String SELECT_TEXTS = "select text from texts;";
	private final static String TEXT_FILE_NAME = "text.txt";
	private final static String WORDS_LIST_FILE_NAME = "word.list";
	private final static String SCRIPT_RESULT_FILE_NAME = "synonym.output";
	
	public static void main(String[] args) throws Exception
	{
		MyConnection selectConnection = new MyConnection();
		WriteToFile writeToFile = new WriteToFile();
		ResultSet resultSet = selectConnection.executeQuery(SELECT_TEXTS);
		new ProcessBuilder("rm", "output.txt").start();
		int i = 1; //only for debug
		while (resultSet.next())
		{
			String text = resultSet.getString("text");
			System.out.println(i++); //only for debug
			writeToFile.write(text, TEXT_FILE_NAME);
			Process proc = new ProcessBuilder(BASH_SCRIPT, TEXT_FILE_NAME).start();
			proc.waitFor();
		}
		selectConnection.close();
		Process proc = new ProcessBuilder("python", PYTHON_SCRIPT, WORDS_LIST_FILE_NAME).start();
		proc.waitFor();
		new WriteToDB().write(SCRIPT_RESULT_FILE_NAME);
	}

}
