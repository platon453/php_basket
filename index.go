//проверка гит откатов
package main
import (
	"fmt"
	"strings"
)

func main() {
	data := []string{"go", "rust", "python"}

	
	mapper := func(fn func(string) string) []string {
		result := make([]string, len(data))
		for i, v := range data {
			result[i] = fn(v)
		}
		return result
	}

	fmt.Println(mapper(strings.ToUpper)) 
}